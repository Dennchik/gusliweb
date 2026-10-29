module.exports = {
  plugins: [
    require('autoprefixer')({
      // grid: 'autoplace',
      cascade: true,
      overrideBrowserslist: ['last 4 versions', '> 2% in RU'],
    }),
  ],
};
