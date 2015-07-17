"use strict";module.exports=function(s){s.initConfig({jshint:{options:{jshintrc:".jshintrc"},all:["Gruntfile.js","/js/*.js","!/js/scripts.min.js"]},sass:{dist:{options:{style:"compressed"},files:{"/css/main.min.css":"/sass/app.scss"}}},uglify:{dist:{files:{"/js/scripts.min.js":["/js/plugins/bootstrap/transition.js","/js/plugins/bootstrap/alert.js","/js/plugins/bootstrap/button.js","/js/plugins/bootstrap/carousel.js","/js/plugins/bootstrap/collapse.js","/js/plugins/bootstrap/dropdown.js","/js/plugins/bootstrap/modal.js","/js/plugins/bootstrap/tooltip.js","/js/plugins/bootstrap/popover.js","/js/plugins/bootstrap/scrollspy.js","/js/plugins/bootstrap/tab.js","/js/plugins/bootstrap/affix.js","/js/plugins/*.js","/js/_*.js","/js/vendor/jquery.fitvids.js","/js/custom/general.js"]},options:{}}},version:{options:{file:"inc/scripts.php",css:"/css/main.min.css",cssHandle:"lsx_main",js:"/js/scripts.min.js",jsHandle:"lsx_scripts"}},watch:{sass:{files:["/sass/*.scss","/sass/bootstrap/*.scss"],tasks:["sass","version"]},js:{files:["<%= jshint.all %>"],tasks:["jshint","uglify","version"]},livereload:{options:{livereload:!1},files:["/css/main.min.css","/js/scripts.min.js","*.php"]}},clean:{dist:["/css/main.min.css","/js/scripts.min.js"]}}),s.loadNpmTasks("grunt-contrib-clean"),s.loadNpmTasks("grunt-contrib-jshint"),s.loadNpmTasks("grunt-contrib-uglify"),s.loadNpmTasks("grunt-contrib-watch"),s.loadNpmTasks("grunt-contrib-sass"),s.loadNpmTasks("grunt-wp-version"),s.registerTask("default",["clean","sass","uglify","version"]),s.registerTask("dev",["watch"])};