const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const JS_ROOT = './assets/javascript';

module.exports = {
    mode: 'production',
    watch: true,
    entry: {
        index: `${JS_ROOT}/script.mjs`,
        edit: `${JS_ROOT}/editUserForm.mjs`,
        create: `${JS_ROOT}/createUserForm.mjs`,
        getSingleUser: `${JS_ROOT}/getUser.mjs`,
    },
    output: {
        filename: '[name].bundle.js',
        path: path.resolve(__dirname, 'assets/dist'),
    },
    module: {
        rules: [{
            test: /\.css$/,
            use: [MiniCssExtractPlugin.loader, 'css-loader']
        }]
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: '[name].css'
        })
    ]
};
