module.exports = (grunt) ->

	grunt.initConfig

		shell:

			# Build documentation
			docs:
				command: 'sphinx-build -b html docs docs-html'

			pdfdocs:
				command: 'sphinx-build -b latex docs docs-pdf && make -C docs-pdf all-pdf'

		# Delete files
		clean:
			docs:      'docs-html/'
			docsCache: 'docs-html/.buildinfo'
			pdfdocs:   'docs-pdf/'

		# Watch for changes
		watch:
			# Reload this file when it changes
			gruntfile:
				files: 'Gruntfile.coffee'

			# Build docs/
			docs:
				options:
					atBegin: true
				files: 'docs/*.rst'
				# Skip clean:docs because I have issues with Chrome not refreshing
				# properly if I happen to refresh too fast and get a Not Found page -
				# for some reason after that I can't see the new version
				tasks: ['clear', 'shell:docs']

			docsAssets:
				files: [
					'docs/*.py'
					'docs/_static/*.*'
				]
				tasks: ['clear', 'clean:docsCache', 'shell:docs']

	# Register tasks
	grunt.registerTask 'default', 'Build HTML documentation',         							['watch']
	grunt.registerTask 'docs',    'Build HTML documentation (docs/ -> docs-html/)', ['clean:docs', 'shell:docs']
	grunt.registerTask 'pdfdocs', 'Build PDF documentation (docs/ -> docs-pdf/)',   ['clean:pdfdocs', 'shell:pdfdocs']

	# Lazy-load plugins
	require('jit-grunt')(grunt)
