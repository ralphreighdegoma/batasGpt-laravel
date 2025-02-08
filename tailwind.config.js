import preset from './vendor/filament/support/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './resources/views/livewire/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                sidebar: {
                    DEFAULT: '#1f2937', // your custom sidebar color (e.g., dark gray)
                },
                primary: {
                    DEFAULT: '#6B21A8', // Set your main purple color
                    light: '#A855F7', // Optional lighter shade
                    dark: '#4C1D95', // Optional darker shade
                },
                success: {
                    DEFAULT: '#16A34A', // Set your main green color
                    light: '#4ADE80',
                    dark: '#15803D',
                },
                danger: {
                    DEFAULT: '#DC2626', // Set your main red color
                    light: '#F87171',
                    dark: '#991B1B',
                },
            },
        },
    },
}