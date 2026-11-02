/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        forest: {
          DEFAULT: '#173B2C',   // Deep forest, primary brand
          light: '#25503D',
          dark: '#0E261C',
        },
        earth: {
          DEFAULT: '#9E5732',   // Terracotta accent, used sparingly
          light: '#E0B094',
          dark: '#8A4A29',
        },
        sand: {
          DEFAULT: '#E6DCCB',   // Warm beige borders and surfaces
          light: '#F3ECE0',
        },
        nature: {
          DEFAULT: '#4F7A52',   // Secondary green for supporting UI
          light: '#71986F',
        },
        cream: '#FAF6EE',       // Warm off-white page background
        charcoal: '#232722',    // Body text
      },
      fontFamily: {
        heading: ['"DM Sans"', 'sans-serif'],
        body: ['"Plus Jakarta Sans"', 'sans-serif'],
      },
      borderRadius: {
        xl: '0.875rem',
        '2xl': '1.25rem',
        '3xl': '1.75rem',
      },
      boxShadow: {
        soft: '0 14px 40px -24px rgba(23, 59, 44, 0.35)',
        lift: '0 24px 60px -32px rgba(23, 59, 44, 0.45)',
      },
      maxWidth: {
        prose: '68ch',
      },
    },
  },
  plugins: [],
}
