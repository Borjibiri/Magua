/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "assets/**/*.js",
    "./templates/**/*.html.twig",
    "/src/**/*.vue",
    ],
  theme: {
    extend: {
      fontFamily: {
        'major-mono': ['Major Mono Display', 'monospace'],
      },
    },
  },
  plugins: [],
}

