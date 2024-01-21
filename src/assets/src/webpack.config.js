const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = {
  entry: {
    "settings/base": "./entries/settings.ts", 
    "frontend/base": "./entries/frontend.js",
    "frontend/style": "./css/frontend/style.scss"
  },
  output: {
    path: path.resolve(__dirname, '..', "dist"),
    filename: "[name].js",
  },
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/,
        exclude: /node_modules/,
        use: ["babel-loader"],
      },
      {
        test: /\.(ts|tsx)$/,
        loader: "ts-loader",
      },
      {
        test: /\.css$/i,
        include: path.resolve(__dirname, 'css'),
        use: ['style-loader', 'css-loader', 'postcss-loader'],
      },
      {
        test: /\.(scss|sass)$/,
        use: [
          MiniCssExtractPlugin.loader, // Extracts CSS into separate files
          'css-loader',   // Translates CSS into CommonJS
          'sass-loader'   // Compiles Sass to CSS
        ],
      },
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({
      // path: path.resolve(__dirname, '..', "dist"),
      filename: "[name].css",
    }),
  ],
  resolve: {
    extensions: ["*", ".js", ".jsx", ".ts", ".tsx"]
  },
  externals: {
    'react': 'React', // Case matters here 
    'react-dom' : 'ReactDOM' // Case matters here 
  }
};