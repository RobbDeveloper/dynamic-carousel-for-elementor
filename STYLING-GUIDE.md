# Styling Guide - Navigation & Pagination

## Navigation Arrows Customization

### Icon Selection

Navigate to **Style → Navigation Arrows** in the Elementor widget panel.

#### 1. Choose Custom Icons
- **Previous Icon**: Click the icon selector to choose from:
  - Font Awesome icons (thousands of options)
  - Custom SVG uploads
  - Default: `fas fa-chevron-left`

- **Next Icon**: Select separately from previous icon
  - Can use different styles for each arrow
  - Default: `fas fa-chevron-right`

**Popular Icon Combinations:**
```
Chevrons:     fa-chevron-left / fa-chevron-right
Arrows:       fa-arrow-left / fa-arrow-right
Angles:       fa-angle-left / fa-angle-right
Long Arrows:  fa-long-arrow-left / fa-long-arrow-right
Caret:        fa-caret-left / fa-caret-right
```

### Color Controls

#### Normal State
- **Icon Color**: Controls the color of the icon itself
  - For font icons: Sets the `color` property
  - For SVG icons: Sets the `fill` property
  - Default: `#ffffff` (white)

- **Background Color**: The button background behind the icon
  - Default: `rgba(0,0,0,0.5)` (semi-transparent black)
  - Supports RGB, RGBA, HEX, and color names

#### Hover State
- **Icon Color**: Color when user hovers over the arrow
  - Default: `#ffffff` (white)
  - Creates smooth transition on hover

- **Background Color**: Button background on hover
  - Default: `rgba(0,0,0,0.8)` (darker semi-transparent black)
  - Can use gradients via CSS

### Size & Positioning
- **Arrow Size**: Controls both button size and icon size
  - Button: Full size (e.g., 40px × 40px)
  - Icon: 50% of button size (automatically calculated)
  - Responsive: Different sizes per device

- **Arrow Position**: Horizontal distance from edge
  - Default: 20px from left/right edges
  - Can use negative values to position outside carousel
  - Responsive per device

### Additional Styling
- **Border**: Add borders to arrow buttons
- **Border Radius**: Round the corners (0 = square, 50% = circle)
- **Box Shadow**: Add depth and elevation effects

---

## Pagination Dots Customization

Navigate to **Style → Pagination Dots** in the Elementor widget panel.

### Color States

#### Normal State
- **Dot Color**: Color for inactive/unselected dots
  - Default: `rgba(255,255,255,0.5)` (semi-transparent white)
  - Shows which slides are available but not current

#### Hover State (NEW!)
- **Dot Color**: Color when hovering over a dot
  - Default: `rgba(255,255,255,0.8)` (more opaque white)
  - Provides visual feedback before clicking
  - Smooth transition effect

#### Active State
- **Dot Color**: Color for the currently active slide
  - Default: `#ffffff` (solid white)
  - Clearly indicates which slide is showing

- **Scale**: Make active dot larger
  - Default: 1.2 (20% larger)
  - Range: 0.5 to 2.0
  - Responsive per device

### Size & Spacing
- **Dot Size**: Diameter of each pagination dot
  - Default: 10px
  - Responsive per device

- **Dot Spacing**: Gap between dots
  - Default: 8px
  - Responsive per device

- **Position from Bottom**: Vertical placement
  - Default: 20px from bottom
  - Responsive per device

### Styling
- **Border Radius**: Shape of dots
  - Default: 50% (perfect circle)
  - Set to 0 for square dots
  - Responsive per device

---

## Common Styling Scenarios

### Scenario 1: Minimalist White Arrows
```
Arrows:
- Icon Color: #ffffff
- Background: transparent
- Border: 2px solid #ffffff
- Border Radius: 50% (circle)
- Hover Icon Color: #000000
- Hover Background: #ffffff
```

### Scenario 2: Bold Colored Navigation
```
Arrows:
- Icon Color: #ffffff
- Background: #FF6B6B (red)
- Hover Icon Color: #ffffff
- Hover Background: #CC5555 (darker red)

Dots:
- Normal: rgba(0,0,0,0.3)
- Hover: rgba(0,0,0,0.5)
- Active: #FF6B6B (matching arrows)
```

### Scenario 3: Subtle Dark Theme
```
Arrows:
- Icon Color: #cccccc
- Background: rgba(0,0,0,0.7)
- Hover Icon Color: #ffffff
- Hover Background: rgba(0,0,0,0.9)

Dots:
- Normal: rgba(255,255,255,0.2)
- Hover: rgba(255,255,255,0.4)
- Active: rgba(255,255,255,0.8)
- Active Scale: 1.5
```

### Scenario 4: Gradient Background Arrows (CSS)
Use Elementor's custom CSS feature:
```css
.carousel-arrow {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}
.carousel-arrow:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%) !important;
}
```

---

## Tips & Best Practices

### Icon Selection
1. **Consistency**: Use icons from the same family (all solid, all outline, etc.)
2. **Weight**: Heavier icons work better at smaller sizes
3. **Testing**: Preview on mobile devices - some icons are clearer than others
4. **Custom SVGs**: Ensure SVGs are optimized and single-color for best results

### Color Contrast
1. **Accessibility**: Maintain sufficient contrast (WCAG AA: 4.5:1 ratio minimum)
2. **Visibility**: Test arrows over various slide content (light/dark images)
3. **Hover States**: Make hover clearly different but not jarring
4. **Active Dots**: Should be clearly distinguishable from inactive

### Responsive Design
1. **Mobile**: Consider smaller arrows (30px instead of 40px)
2. **Tablet**: Medium sizes often work well
3. **Desktop**: Larger arrows for easier clicking
4. **Test**: Always preview on actual devices, not just responsive mode

### Animation & Performance
1. **Transitions**: All color changes have smooth 0.3s transitions
2. **Hover**: Changes happen on hover, not just click
3. **Performance**: SVG icons typically perform better than font icons
4. **Disabled States**: Arrows automatically become semi-transparent when disabled (at start/end if not looping)

---

## Troubleshooting

**Icons not showing?**
- Ensure Font Awesome is loaded (Elementor loads it by default)
- For custom SVGs, check the file is properly uploaded
- Inspect element to verify icon HTML is rendering

**Colors not applying to SVG?**
- SVG fill color is controlled separately
- Check that SVG doesn't have inline styles that override
- Use the icon color controls, not generic color controls

**Hover not working?**
- Clear Elementor cache
- Check browser console for JavaScript errors
- Ensure CSS transitions aren't disabled globally

**Dots overlapping slides?**
- Increase "Position from Bottom" value
- Reduce dot size on mobile
- Consider adjusting slide border radius

---

## Advanced Customization

For advanced users, you can use Elementor's **Custom CSS** feature:

### Example: Pulse Animation on Active Dot
```css
selector .carousel-dot.active {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
```

### Example: Square Arrows with Rounded Corners
```css
selector .carousel-arrow {
    border-radius: 8px !important;
}
```

### Example: Different Icon Sizes for Each Arrow
```css
selector .carousel-arrow-left svg {
    width: 60% !important;
    height: 60% !important;
}

selector .carousel-arrow-right svg {
    width: 40% !important;
    height: 40% !important;
}
```

---

## Support

If you need help with styling, please provide:
1. Screenshot of your current styling
2. What you're trying to achieve
3. Device/browser information
4. Any custom CSS you've added
