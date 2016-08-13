/*jslint node: true*/
module.exports = function (grunt) {
    'use strict';

    grunt.loadNpmTasks('grunt-jslint');
    grunt.loadNpmTasks('grunt-phpunit');
    grunt.loadNpmTasks('grunt-phpcs');

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
        },
        phpcs: {
            options: {
                standard: 'PSR2',
                bin: 'vendor/bin/phpcs'
            },
            php: {
                src: ['classes/*.php', '*.php']
            },
            tests: {
                src: 'tests/*.php'
            }
        }
    });

    grunt.registerTask('lint', ['jslint', 'phpcs']);
    grunt.registerTask('test', ['phpunit']);
};
