const colors = require('tailwindcss/colors')

module.exports = {
    prefix: '',
    important: true,
    purge: {
        enabled: false,
        content: [
            './src/app/**/*.{html,ts}',
        ]
    },
    darkMode: 'class', // or 'media' or 'class'
    theme: {
        extend: {
            colors: {
                gray: colors.trueGray,
                orange: colors.orange
            },
            zIndex: {
                '-10': '-10',
            },
            textColor: {
                'primary': '#2F4858',
                'secondary': '#039590',
                'danger': '#EB9928',
                'success': '#9BDE7E',
                'cta': '#039590'
            },
            backgroundColor: {
                'primary': '#2F4858',
                'secondary': '#039590',
                'danger': '#EB9928',
                'success': '#9BDE7E'
            },
            borderColor: {
                'primary': '#2F4858',
                'secondary': '#039590',
                'danger': '#EB9928',
                'success': '#9BDE7E'
            }
        }

    },
    variants: {
        extend: {
            opacity: ['disabled'],
            backgroundColor: ['checked'],
            borderColor: ['checked'],
        },
    },
    plugins: [require('@tailwindcss/typography')],
}
