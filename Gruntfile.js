/*jslint node: true*/
module.exports = function (grunt) {
    'use strict';

    grunt.loadNpmTasks('grunt-jslint');
    grunt.loadNpmTasks('grunt-phpunit');
    grunt.loadNpmTasks('grunt-phpcs');
    grunt.loadNpmTasks('grunt-jsonlint');
    grunt.loadNpmTasks('grunt-fixpack');
    grunt.loadNpmTasks('grunt-phpdocumentor');

    grunt.initConfig({
        jslint: {
            Gruntfile: {
                src: 'Gruntfile.js'
            }
        },
        phpunit: {
            options: {
                bin: 'vendor/bin/phpunit',
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
                bin: 'vendor/bin/phpcs',
                warningSeverity: 0
            },
            php: {
                src: ['classes/*.php', '*.php']
            },
            tests: {
                src: 'tests/*.php'
            }
        },
        jsonlint: {
            manifests: {
                src: '*.json',
                options: {
                    format: true
                }
            }
        },
        fixpack: {
            package: {
                src: 'package.json'
            }
        },
        phpdocumentor: {
            doc: {
                options: {
                    directory: 'classes/,tests/'
                }
            }
        }
    });

    grunt.registerTask('lint', ['jslint', 'fixpack', 'jsonlint', 'phpcs']);
    grunt.registerTask('test', ['phpunit']);
    grunt.registerTask('doc', ['phpdocumentor']);
};
