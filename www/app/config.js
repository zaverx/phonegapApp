// Set the require.js configuration for your application.
require.config({

  // Initialize the application with the main application file and the JamJS
  // generated configuration file.
  deps: ["../vendor/jam/require.config", "main"],

  paths: {
    // Put paths here.
      tpl: "../vendor/js/libs/tpl",
      pullRefresh : "../vendor/js/libs/pullRefresh"
  },

  shim: {
    // Put shims here.
  }

});
