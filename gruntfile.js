module.exports = function (grunt) {
    'use strict';

    var autoprefixer = require('autoprefixer')({
        browsers: [
            'Chrome >= 45',
            'Firefox >= 40',
            'Edge >= 12',
            'Explorer >= 11',
            'iOS >= 9',
            'Safari >= 9',
            'Android 2.3',
            'Android >= 4',
            'Opera >= 30'
        ]
    });

    // Project configuration.
    grunt.initConfig({
        // Task configuration
        // -------------------------------------------------------------------------------

        // Optimize Image
        //
        imagemin: {
            png: {
                options: {
                    optimizationLevel: 7
                },
                files  : [
                    {
                        // Set to true to enable the following options…
                        expand: true,
                        // cwd is 'current working directory'
                        cwd   : 'resources/images',
                        src   : ['**/*.png'],
                        // Could also match cwd line above. i.e. project-directory/img/
                        dest  : 'web/assets/img/',
                        ext   : '.png'
                    }
                ]
            }
        },



        // Watch on files
        //
        watch: {

            css_custom: {
                files: ['resources/css/*.css'],
                tasks: ['concat:custom', 'cssmin:custom']
            },
            js_script : {
                files: ['resources/js/nao.js'],
                tasks: ['uglify:script']
            },
            img_png   : {
                files: ['resources/images/**/*.png'],
                tasks: ['imagemin:png']
            }
        },

        // Clean files and directories
        //
        clean: {
            before_copy: ['web/assets']
        },

        // Copy files
        //
        copy: {
            font: {
                files: [
                    {expand: true, cwd: 'resources/vendor/materialize/fonts', src: ['**'], dest: 'web/assets/fonts/'}
                ]
            },
            jpg: {
                files: [
                    {expand: true, cwd: 'resources/images', src: ['**/*.jpg'], dest: 'web/assets/img/'}
                ]
            },
            fixtures: {
                files: [
                    {expand: true, cwd: 'resources/fixtures', src: ['**/*.*'], dest: 'web/images/'}
                ]
            },
            js: {
                files: [
                    {expand: true, cwd: 'resources/js', src: ['paginate.js'], dest: 'web/assets/js/'}
                ]

            }
        },

        // Concat plugins to make core.min
        //
        concat: {
            core: {
                files: {
                    // Javascript
                    'web/assets/js/core.min.js': [
                        'resources/vendor/jquery/jquery-3.2.1.min.js',
                        'resources/vendor/materialize/js/materialize.min.js',
                        'resources/vendor/materialize/js/material-dialog.min.js',
                        'resources/vendor/materialize-autocomplete/jquery.materialize-autocomplete.min.js',
                        'resources/vendor/jquery-validator/jquery.validate.min.js',
                        'resources/vendor/jquery-validator/additional-methods.min.js',
                        'resources/vendor/jquery-validator/localization/messages_fr.js',
                        'resources/vendor/materialNote/js/materialnote.js',
                        'resources/vendor/gmaps/gmaps.min.js'
                    ],


                    // CSS
                    'web/assets/css/core.min.css': [
                        'resources/vendor/bootstrap/css/bootstrap.min.css',
                        'resources/vendor/materialize/css/materialize.min.css',
                    ]
                }
            },

            custom: {
                files: {
                    'web/assets/css/custom.min.css': [
                        'resources/css/nao.css',
                        'resources/css/materialnote.css'

                    ]
                }
            }
        },

        // Uglify JS files
        //
        uglify: {
            nao : {
                options: {
                    mangle: true,
                    preserveComments: false
                },
                files: {
                    'web/assets/js/nao.min.js': ['resources/js/nao.js', 'resources/js/social.js']
                }
            },
        },

        // Do some post processing on CSS files
        postcss: {
            options : {
                processors: [
                    autoprefixer,
                    require('postcss-flexbugs-fixes')
                ]
            },
            compressed: {
                src: 'web/assets/css/custom.min.css'
            }
        },

        // Minify CSS files
        cssmin: {
            options: {
                compatibility      : 'ie9',
                keepSpecialComments: false,
                sourceMap          : false,
                advanced           : false
            },
            core   : {
                src : 'web/assets/css/core.min.css',
                dest: 'web/assets/css/core.min.css'
            },
            custom : {
                src : 'web/assets/css/custom.min.css',
                dest: 'web/assets/css/custom.min.css'
            }
        }

        // -------------------------------------------------------------------------------
        // END Task configuration

    });

    // These plugins provide necessary tasks.
    require('load-grunt-tasks')(grunt, {scope: 'devDependencies', pattern: ['grunt-*']});
    require('autoprefixer')(grunt);
    //require('time-grunt')(grunt);

    // Run "grunt" to watch SCSS and JS files as well as running browser-sync
    grunt.registerTask('default', ['dist', 'watch']);


    // Run "grunt dist" to publish the template in a ./dist folder
    grunt.registerTask('dist',
        [
            'clean:before_copy',
            'dev',
            'imagemin'
        ]
    );
    grunt.registerTask('dev',
        [
            'copy',
            'concat',
            'uglify',
            'cssmin',
            'postcss'
        ]
    );
};
