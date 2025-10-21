export function useColorUtils() {
    // Calculate luminance of a color
    const getLuminance = (hex) => {
        // Remove # if present
        hex = hex.replace('#', '');

        // Convert to RGB
        const r = parseInt(hex.substr(0, 2), 16) / 255;
        const g = parseInt(hex.substr(2, 2), 16) / 255;
        const b = parseInt(hex.substr(4, 2), 16) / 255;

        // Calculate relative luminance
        const [rs, gs, bs] = [r, g, b].map(c => {
            return c <= 0.03928 ? c / 12.92 : Math.pow((c + 0.055) / 1.055, 2.4);
        });

        return 0.2126 * rs + 0.7152 * gs + 0.0722 * bs;
    };

    // Determine if color is light or dark (threshold at 0.5)
    const isLightColor = (hex) => {
        const luminance = getLuminance(hex);
        console.log('Color:', hex, 'Luminance:', luminance, 'Is Light:', luminance > 0.5);
        return luminance > 0.5;
    };

    // Get contrasting text color
    const getContrastColor = (backgroundColor) => {
        const contrast = isLightColor(backgroundColor) ? '#000000' : '#14539A';
        console.log('Background:', backgroundColor, 'Text Color:', contrast);
        return contrast;
    };

    // Get contrasting text color with opacity
    const getContrastColorWithOpacity = (backgroundColor, opacity = 1) => {
        const isDark = !isLightColor(backgroundColor);
        return isDark
            ? `rgba(255, 255, 255, ${opacity})`
            : `rgba(0, 0, 0, ${opacity})`;
    };

    return {
        getLuminance,
        isLightColor,
        getContrastColor,
        getContrastColorWithOpacity,
    };
}
