const path = require('path');
const mix = require('laravel-mix');
const webpack = require('webpack')

require('dotenv').config({
  path: path.join(__dirname, 'resources/js/.env')
})
console.log(process.env)

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

const dotenvplugin = new webpack.DefinePlugin({
  'process.env': {
    APP_URL: JSON.stringify(process.env.APP_URL || ''),
    NGINX_USER: JSON.stringify(process.env.NGINX_USER || ''),
    NGINX_PWD: JSON.stringify(process.env.NGINX_PWD || '')
  }
})

const config = {
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/js'),
      sass: path.resolve(__dirname, 'resources/sass'),
    },
  },
  watchOptions: {
    poll: 2000,
    ignored: / node_modules /,
  },
  node: {
    fs: "empty"
  },
  plugins: [
    dotenvplugin,
  ],
}

mix
  .webpackConfig(config)
  .js('resources/js/app.js', 'public/js')
  .sass('resources/sass/app.scss', 'public/css')
  .extract(['vue', 'axios']);
