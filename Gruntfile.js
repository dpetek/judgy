module.exports = function(grunt) {

    // 1. All configuration goes here
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        "bower-install-simple": {
            options: {
                color: true
            },
            "prod": {
                options: {
                    production: true
                }
            },
            "dev": {
                options: {
                    production: false
                }
            }
        },
        wiredep: {

            task: {

                // Point to the files that should be updated when
                // you run `grunt wiredep`
                src: [
                    'module/Judge/view/**/*.phtml',   // .html support...
                ],


                options: {
                    ignorePath: '/public'
                }
            }
        }
    });
    grunt.loadNpmTasks("grunt-bower-install-simple");
    grunt.loadNpmTasks('grunt-wiredep');

    grunt.registerTask('default', [ 'bower-install-simple', 'wiredep' ]);

};