const ExtractTextPlugin = require("extract-text-webpack-plugin");
const webpack = require('webpack');
const path = require('path');

let conf = {
    entry: './app.js',
    output: {
        path: path.resolve(__dirname,'./../local/assets/build'),
        filename: 'app.js',
    },
    module:{
        rules:[
            {
                test: /\.(png|jpg|gif)$/i,
                use: [
                    {
                        loader: 'url-loader',
                        options: {
                            limit: 8192
                        }
                    }
                ]
            },
            { test: /\.js$/,
                exclude: /node_modules/,
                loader: "babel-loader"
            },
            {
                test: /\.(css)/,
                use: ExtractTextPlugin.extract({
                    fallback: "style-loader",
                    use: ['css-loader']
                })
            },
            {
                test: /\.(scss)/,
                use: ExtractTextPlugin.extract({
                    fallback: "style-loader",
                    use: ['css-loader','sass-loader']
                })
            },
        ]
    },
    plugins: [
        // new webpack.ProvidePlugin({
        //     $: 'jquery',
        //     jQuery: 'jquery',
        //     'window.jQuery': 'jquery'
        // }),
        new ExtractTextPlugin("styles.css"),
    ],
};

module.exports = (env, options) => {
    let production = options.mode === 'production';

    conf.devtool = production ? false :'eval-sourcemap';

    return conf;
};