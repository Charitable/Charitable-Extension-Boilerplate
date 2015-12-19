/*global module:false*/
/*global require:false*/
/*jshint -W097*/
"use strict";

module.exports = function(grunt) {

    var _name = grunt.option( 'name' ), 
        _class = grunt.option( 'class' ), 
        _textdomain = grunt.option( 'textdomain' );    
 
    // load all grunt tasks
    require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);
 
    grunt.initConfig({

        pkg: grunt.file.readJSON('package.json'),

        // Clean up build directory
        clean: {
            main: [ 'build/' + _textdomain ]
        },

        // Do the text replacement
        replace: {
            main: {
                options: {
                    patterns: [
                        {
                            match: "Extension Boilerplate", 
                            replacement: _name
                        },
                        {
                            match: "extension boilerplate", 
                            replacement: _name.toLowerCase()
                        },
                        {
                            match: "Charitable_Extension_Boilerplate", 
                            replacement: _class
                        },
                        {
                            match: "charitable_extension_boilerplate",
                            replacement: _class.toLowerCase()
                        },
                        {
                            match: "charitable-extension-boilerplate", 
                            replacement: _textdomain
                        },                        
                    ], 
                    usePrefix: false
                },
                files: [
                    {
                        expand: true,
                        cwd: 'src/',
                        src: [ '**' ], 
                        dest: 'build/' + _textdomain + '/'
                    }
                ]
            },
        },

        // Rename the files
        rename: {
            main: {
                files: [
                    {
                        src: [ 'build/' + _textdomain + '/charitable-extension-boilerplate.php' ], 
                        dest: 'build/' + _textdomain + '/' + _textdomain + '.php'
                    },
                    {
                        src: [ 'build/' + _textdomain + '/languages/charitable-extension-boilerplate.pot' ], 
                        dest: 'build/' + _textdomain + '/languages/' + _textdomain + '.pot'
                    },
                    {
                        src: [ 'build/' + _textdomain + '/includes/charitable-extension-boilerplate-core-functions.php' ], 
                        dest: 'build/' + _textdomain + '/includes/' + _textdomain + '-core-functions.php'
                    },
                    {
                        src: [ 'build/' + _textdomain + '/includes/class-charitable-extension-boilerplate-template.php' ], 
                        dest: 'build/' + _textdomain + '/includes/class-' + _textdomain + '-template.php'
                    },
                    {
                        src: [ 'build/' + _textdomain + '/includes/class-charitable-extension-boilerplate-upgrade.php' ], 
                        dest: 'build/' + _textdomain + '/includes/class-' + _textdomain + '-upgrade.php'
                    },
                    {
                        src: [ 'build/' + _textdomain + '/includes/class-charitable-extension-boilerplate.php' ], 
                        dest: 'build/' + _textdomain + '/includes/class-' + _textdomain + '.php'
                    },                    
                    {
                        src: [ 'build/' + _textdomain + '/includes/admin/charitable-extension-boilerplate-admin-hooks.php' ], 
                        dest: 'build/' + _textdomain + '/includes/admin/' + _textdomain + '-admin-hooks.php'
                    },
                    {
                        src: [ 'build/' + _textdomain + '/includes/admin/class-charitable-extension-boilerplate-admin.php' ], 
                        dest: 'build/' + _textdomain + '/includes/admin/class-' + _textdomain + '-admin.php'
                    },
                    {
                        src: [ 'build/' + _textdomain + '/includes/i18n/class-charitable-extension-boilerplate-i18n.php' ], 
                        dest: 'build/' + _textdomain + '/includes/i18n/class-' + _textdomain + '-i18n.php'
                    },
                ]
            }            
        },  

        // Copy the src files into a new directory
        copy: {
            main: {
                expand: true,
                cwd: 'src/',                
                src: [ '**' ],
                dest: 'build/' + _textdomain + '/',
            }
        }
    });

    // Build task(s).
    grunt.registerTask( 'build', function() {
        grunt.task.run( 'clean' );
        grunt.task.run( 'replace' );
        grunt.task.run( 'rename' );
    });
};