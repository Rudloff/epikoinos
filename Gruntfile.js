/*jslint node: true*/
module.exports = function (grunt) {
    'use strict';

    grunt.loadNpmTasks('grunt-jslint');
    grunt.loadNpmTasks('grunt-phpunit');

    grunt.initConfig({
        jslint: {
            Gruntfile: {
                src: 'Gruntfile.js'
            }
        },
        phpunit: {
            options: {
                bin: 'php -dzend_extension=xdebug.so ./vendor/bin/phpunit',
                stopOnError: true,
                stopOnFailure: true,
                followOutput: true
            },
            classes: {
                dir: 'tests/'
            }
        }
    });

    grunt.registerTask('lint', ['jslint']);
    grunt.registerTask('test', ['phpunit']);
};