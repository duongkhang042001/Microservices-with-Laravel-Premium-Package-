module.exports = {
    css: {
      loaderOptions: {
        sass: {
          additionalData: `
          @import '@/assets/_variables.scss';
          @import '~bootstrap/scss/bootstrap.scss';
          @import '~bootswatch/dist/darkly/_bootswatch.scss';
          @import '~toastr/build/toastr.min.css';`
        }
      }
    }
  }
