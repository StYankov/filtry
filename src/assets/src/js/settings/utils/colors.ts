export function isColorLight( hex: string ): boolean {
    const { r, g, b } = hexToRgb(hex);

    // Calculate relative luminance
    const luminance = 0.2126 * (r / 255) + 0.7152 * (g / 255) + 0.0722 * (b / 255);

    // Determine if color is light or dark
    return luminance > 0.5;
}

export function hexToRgb( hex: string ): { r: number, g: number, b: number } | null {
    hex = hex.replace( '#', '' );

    if ( hex.length === 3 ) {
        hex = hex.split( '' ).map( char => char + char ).join( '' );
    }

    const r = parseInt( hex.slice( 0, 2 ), 16 );
    const g = parseInt( hex.slice( 2, 4 ), 16 );
    const b = parseInt( hex.slice( 4, 6 ), 16 );

    return { r, g, b };
}