/*global module:false*/
/*global require:false*/
/*jshint -W097*/
"use strict";

module.exports = function(grunt) {
 
    // load all grunt tasks
    require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);
 
    grunt.initConfig({
 
        // watch for changes and trigger compass, jshint, uglify and livereload
        watch: {                        
            sass: {
                files: [ 'assets/css/scss/*.{scss,sass}' ],
                tasks: ['sass:dist']
            },
            sync: {
                files: [
                    'assets/',
                    'assets/**',                    
                    'includes', 
                    'includes/**', 
                    'languages', 
                    'languages/**', 
                    'templates',
                    'templates/**',
                    'charitable-extension-boilerplate.php'
                ],
                tasks: ['sync:dist']
            }     
        },

        // Sass
        // sass: {
        //     dist: {
        //         files: {
        //             'assets/css/charitable-extension-boilerplate.css' : 'assets/css/scss/charitable-extension-boilerplate.scss'
        //         }
        //     }
        // },

        // Sync
        sync: {                
            dist: {
                files: [
                    // includes files within path
                    {
                        src: [   
                            'assets/',
                            'assets/**',                    
                            'includes', 
                            'includes/**', 
                            'languages', 
                            'languages/**', 
                            'templates',
                            'templates/**',
                            'charitable-extension-boilerplate.php'                                
                        ], 
                        dest: '../../plugins/charitable-extension-boilerplate'
                    }
                ], 
                verbose: true, 
                updateAndDelete: true
            }
        },

        // make POT file
        makepot: {
            target: {
                options: {
                    cwd: '',
                    domainPath: 'languages',
                    mainFile: 'charitable-extension-boilerplate.php',
                    potFilename: 'charitable-extension-boilerplate.pot',
                    type: 'wp-plugin',
                    updateTimestamp: true
                }
            }
        }

    });

    // register task
    // grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.registerTask('default', ['watch']);
    grunt.registerTask('build', ['makepot']);
};