/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./*.{html,js,php}"],
  theme: {
    fontFamily: {
      'poppins':['poppins','san-serif'],
    },
    extend: {},
  },
  daisyui: {
    themes: [
      {
        mytheme: {
          primary: "#8b5cf6",

          secondary: "#d8b4fe",

          third: "#4c2f8f",

          accent: "#111827",

          neutral: "#ffffff",

          "base-100": "#e5e7eb",

          info: "#22d3ee",

          success: "#86efac",

          warning: "#facc15",

          error: "#f87171",
        },
      },
    ],
  },
  plugins: [require("daisyui")],
};
