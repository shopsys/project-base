const em = (value) => value / 16 + 'em';

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ['./pages/**/*.{js,ts,jsx,tsx}', './components/**/*.{js,ts,jsx,tsx}'],
    theme: {
        screens: {
            xs: em(320),
            sm: em(480),
            md: em(600),
            lg: em(769),
            vl: em(1024),
            xl: em(1240),
            xxl: em(1560),
        },
        colors: {
            text: '#25283D',
            textAccent: '#004EB6',
            textInverted: '#FFFFFF',
            textDisabled: '#727588',
            textSuccess: '#00CDBE',
            textError: '#EC5353',

            link: '#004EB6',
            linkDisabled: '#7AA1D5',
            linkHovered: '#003479',
            linkInverted: '#FFFFFF',
            linkInvertedDisabled: '#727588',
            linkInvertedHovered: '#FFF0C4',

            borderAccent: '#7892BC',
            borderAccentLess: '#E0E0E0',
            borderAccentSuccess: '#20D3C6',
            borderAccentError: '#EC5353',

            background: '#FFFFFF',
            backgroundMore: '#FAFAFA',
            backgroundMost: '#ECECEC',
            backgroundBrand: '#0F00A0',
            backgroundBrandLess: '#065FDB',
            backgroundAccent: '#009AFF',
            backgroundAccentLess: '#F4FAFF',
            backgroundAccentMore: '#008AE5',
            backgroundDark: '#25283D',
            backgroundError: '#EC5353',
            backgroundSuccess: '#20D3C6',
            backgroundWarning: '#FCBD46',

            price: '#004EB6',

            actionPrimaryText: '#FFFFFF',
            actionPrimaryTextActive: '#FFFFFF',
            actionPrimaryTextDisabled: '#FAFAFA',
            actionPrimaryTextHovered: '#FFFFFF',
            actionPrimaryBackground: '#00CDBE',
            actionPrimaryBackgroundActive: '#004EB6',
            actionPrimaryBackgroundDisabled: '#8AE4DD',
            actionPrimaryBackgroundHovered: '#01BEB0',
            actionPrimaryBorder: '#00CDBE',
            actionPrimaryBorderActive: '#01BEB0',
            actionPrimaryBorderDisabled: '#8AE4DD',
            actionPrimaryBorderHovered: '#004EB6',
            actionInvertedText: '#004EB6',
            actionInvertedTextActive: '#FFFFFF',
            actionInvertedTextDisabled: '#B6C3D8',
            actionInvertedTextHovered: '#FFFFFF',
            actionInvertedBackground: '#FFFFFF',
            actionInvertedBackgroundActive: '#004EB6',
            actionInvertedBackgroundDisabled: '#FAFAFA',
            actionInvertedBackgroundHovered: '#004EB6',
            actionInvertedBorder: '#004EB6',
            actionInvertedBorderActive: '#004EB6',
            actionInvertedBorderDisabled: '#B6C3D8',
            actionInvertedBorderHovered: '#004EB6',

            activeIconFull: '#EC5353',

            availabilityInStock: '#00CDBE',
            availabilityOutOfStock: '#EC5353',

            openingStatusOpen: '#00CDBE',
            openingStatusClosed: '#EC5353',
            openingStatusOpenToday: '#FCBD46',

            inputText: '#004EB6',
            inputTextActive: '#004EB6',
            inputTextDisabled: '#727588',
            inputTextHovered: '#004EB6',
            inputTextInverted: '#FFFFFF',
            inputPlaceholder: '#7892BC',
            inputPlaceholderActive: '#3967B2',
            inputPlaceholderDisabled: '#AFBBCF',
            inputPlaceholderHovered: '#5C81BE',
            inputBackground: '#FFFFFF',
            inputBackgroundActive: '#FFFFFF',
            inputBackgroundDisabled: '#E3E3E3',
            inputBackgroundHovered: '#FFFFFF',
            inputBorder: '#7892BC',
            inputBorderActive: '#004EB6',
            inputBorderDisabled: '#AFBBCF',
            inputBorderHovered: '#004EB6',
            inputError: '#EC5353',

            tableBackground: '#FFFFFF',
            tableBackgroundContrast: '#FAFAFA',
            tableBackgroundHeader: '#3967B2',
            tableText: '#25283D',
            tableTextHeader: '#FFFFFF',

            labelLinkText: '#FFFFFF',
            labelLinkTextActive: '#FFFFFF',
            labelLinkTextDisabled: '#E3E3E3',
            labelLinkTextHovered: '#FFFFFF',
            labelLinkBackground: '#7892BC',
            labelLinkBackgroundActive: '#3967B2',
            labelLinkBackgroundDisabled: '#AFBBCF',
            labelLinkBackgroundHovered: '#3967B2',
            labelLinkBorder: '#7892BC',
            labelLinkBorderActive: '#3967B2',
            labelLinkBorderDisabled: '#AFBBCF',
            labelLinkBorderHovered: '#3967B2',

            imageOverlay: 'rgba(201, 201, 201, 0.5)',
            overlay: 'rgba(37, 40, 61, 0.5)',
        },
        fontFamily: {
            default: ['var(--font-inter)'],
            secondary: ['var(--font-raleway)'],
        },
        zIndex: {
            hidden: -1000,
            above: 1,
            flag: 10,
            menu: 1010,
            aboveMenu: 1020,
            overlay: 1030,
            mobileMenu: 1040,
            aboveMobileMenu: 1050,
            cart: 6000,
            aboveOverlay: 10001,
            maximum: 10100,
        },
        extend: {
            lineHeight: {
                DEFAULT: 1.3,
            },
            fontSize: {
                clamp: 'clamp(16px, 4vw, 22px)',
            },
            borderRadius: {
                DEFAULT: '0.1875rem',
            },
        },
        plugins: [],
    },
};
