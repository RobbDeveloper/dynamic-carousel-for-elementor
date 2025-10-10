# Dynamic Carousel Widget for Elementor

A fully customizable Elementor carousel widget that supports images, videos, ACF galleries, and Elementor templates with dynamic widths and responsive controls.

## Features

- **Multiple slide types**: Single images, ACF galleries, videos (YouTube, Vimeo, self-hosted), Elementor templates
- **Dynamic width system**: Slides automatically size based on aspect ratios
- **Fully responsive**: All parameters adjustable per device (desktop/tablet/mobile)
- **ACF integration**: Pull data directly from current post using ACF fields
- **Dynamic tags support**: Use Elementor dynamic tags for images, videos, and templates
- **Advanced controls**: Navigation arrows, pagination dots, autoplay, infinite loop
- **Touch/drag support**: Mobile-friendly swipe gestures
- **Keyboard navigation**: Arrow key support for accessibility

## Installation

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin through WordPress Plugins menu
3. The widget will appear in Elementor under "General" category

## Usage

### Using ACF Gallery Fields

To display an ACF gallery from the current post:

1. Add a slide and select "ACF Gallery" as the slide type
2. Enter your ACF gallery field name (e.g., `gallery_images`)
3. Choose the aspect ratio for gallery images
4. The widget will automatically fetch the gallery from the current post

**Important**: Make sure the ACF gallery field exists and has images in the current post.

### Using Dynamic Tags for Images

To use dynamic content for images:

1. Add a slide and select "Single Image"
2. In the image control, click the dynamic tags icon
3. Select your dynamic source (e.g., ACF Field, Featured Image, etc.)
4. The image will pull from the current post

### Using Dynamic Tags for Videos

For video URLs from ACF fields:

1. Add a slide and select "Video"
2. Choose your video type (YouTube, Vimeo, or Self Hosted)
3. In the URL field, click the dynamic tags icon
4. Select your ACF field containing the video URL
5. The video URL will pull from the current post

### Using Elementor Templates

To embed an Elementor template:

1. Add a slide and select "Elementor Template"
2. Enter the template ID (numeric) or use dynamic tags
3. Set the template width (responsive per device)
4. You can also use shortcode format: `[elementor-template id="123"]`

## Settings Overview

### Carousel Settings

- **Carousel Height**: Fixed height for all slides (responsive)
- **Slide Spacing**: Gap between slides (responsive)
- **Autoplay**: Enable automatic sliding
- **Autoplay Speed**: Time between slides in milliseconds
- **Infinite Loop**: Enable continuous looping
- **Transition Speed**: Animation speed in milliseconds (responsive)

### Navigation Arrows

- **Show Arrows**: Toggle visibility
- **Arrow Size**: Customize button size (responsive)
- **Arrow Position**: Horizontal position from edge (responsive)
- **Colors**: Normal and hover states
- **Border & Border Radius**: Full styling control

### Pagination Dots

- **Show Pagination**: Toggle visibility
- **Position from Bottom**: Vertical positioning (responsive)
- **Dot Size**: Size of pagination dots (responsive)
- **Dot Spacing**: Gap between dots (responsive)
- **Colors**: Normal and active states
- **Scale on Active**: Enlarge active dot (responsive)

### Slide Styling

- **Border Radius**: Round slide corners (responsive)

## Aspect Ratios

Available preset ratios:
- 1:1 (Square)
- 2:3 (Portrait)
- 3:2 (Landscape)
- 4:3 (Standard)
- 16:9 (Widescreen)
- 21:9 (Ultrawide)
- Custom (specify your own ratio)

For ACF galleries, you can also use "Original" to maintain each image's native aspect ratio.

## How Dynamic Widths Work

The carousel has a **fixed height** (set in Carousel Settings), and each slide's **width is calculated automatically** based on:

1. The carousel height
2. The slide's aspect ratio

For example:
- Carousel height: 500px
- Slide aspect ratio: 3:2
- Calculated width: 750px (500 × 1.5)

This allows you to mix different aspect ratios in one carousel:
- A 2:3 portrait video (333px wide)
- A 16:9 landscape image (889px wide)
- A custom template (any width you set)

The carousel smoothly scrolls through slides of varying widths, showing 1.5, 2, or even 3 slides at once depending on their sizes.

## Keyboard Controls

- **Left Arrow**: Previous slide
- **Right Arrow**: Next slide

## Touch/Drag Support

- Swipe left/right on mobile
- Click and drag on desktop
- Automatic resistance at boundaries

## Tips & Best Practices

1. **ACF Gallery**: Make sure ACF plugin is installed and the field name matches exactly
2. **Dynamic Tags**: Always test with actual post data, not in the template editor
3. **Mixed Ratios**: Use consistent heights but varied widths for the best visual effect
4. **Performance**: Limit the number of slides for better performance (especially with templates)
5. **Responsive**: Test on all devices - heights and spacings can differ per breakpoint

## Troubleshooting

**ACF gallery not showing:**
- Verify ACF plugin is active
- Check the field name is correct
- Make sure you're viewing a post with gallery data
- In Elementor editor, you may see a placeholder - preview the page to see actual content

**Dynamic tags not working:**
- Ensure you're using Elementor Pro (required for dynamic tags)
- Verify the ACF field exists on the current post
- Check field type matches (URL field for videos, image field for images)

**Carousel not sliding:**
- Check browser console for JavaScript errors
- Ensure jQuery is loaded
- Verify the widget scripts are enqueued properly

**Slides not sizing correctly:**
- Check carousel height is set
- Verify aspect ratio is selected for each slide
- Use browser dev tools to inspect calculated widths

## Browser Compatibility

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Requirements

- WordPress 5.0+
- Elementor 3.0.0+
- PHP 7.0+
- ACF plugin (for ACF gallery features)
- Elementor Pro (for dynamic tags)

## Support

For issues and feature requests, please contact the plugin author.

## License

This plugin is provided as-is for use with Elementor.
