// tailwind.config.js
module.exports = {
  darkMode: false, // 👈 désactive totalement les classes dark:
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
