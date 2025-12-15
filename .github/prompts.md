Mục tiêu: Tạo một website e-commerce hiện đại, thanh lịch và thân thiện người dùng. Thiết kế theo xu hướng "bo tròn" (rounded UI), sử dụng hiệu ứng "liquid glass"/frosted-glass (như iOS) cho các component chính (cards, navbar, modals). Giao diện cần tối ưu cho mobile-first, responsive, và tập trung chuyển đổi (clear product images, CTA mạnh).

Yêu cầu chung:

-   Tone: hiện đại, tối giản, cao cấp nhưng ấm áp.
-   Hệ màu: 1 màu chủ đạo (ví dụ teal/cerulean), 1 màu accent (ví dụ coral), nền nhẹ (near-white / very light gray) và các layer translucent.
-   Typography: Sans-serif hiện đại, lớn cho headings (H1-H3), readable body (16–18px). Chú ý line-height và spacing.
-   Border radius: thành phần UI lớn (16–28px) cho containers; medium (10–14px) cho buttons; small (6–8px) cho inputs.
-   Shadow & depth: mềm mại, mờ, không quá nặng.
-   Accessibility: contrast đủ cho chữ chính, keyboard navigable, ARIA labels cho thành phần tương tác.
-   Performance: tối ưu ảnh (srcset), lazy-load hình, CSS tối giản, animation nhẹ.

Component & layout (ưu tiên, từng phần rõ ràng):

1. Header / Nav

    - Thanh nav trong suốt (translucent) với blur backdrop-filter khi scroll.
    - Logo left, search center (expandable on mobile), icons cart/profile right.
    - Sticky but not intrusive; khi scroll thu gọn height.

2. Hero

    - Large promo area: image sản phẩm + gradient overlay + primary CTA.
    - Card hero nhỏ dạng liquid-glass ở góc chứa thông tin flash offer.

3. Product Grid / Cards

    - Cards bo tròn, background translucent (glass) hoặc solid tùy context.
    - Image lớn, subtle hover lift + soft shadow + slight blur overlay with “Add” CTA.
    - Kèm price, rating, short tags (pill rounded).

4. Product Page

    - Gallery lớn bên trái, sticky buy box bên phải trên desktop (liquid-glass box).
    - Thumbnails, zoom on hover, mobile swipable carousel.
    - Options (size/color) as pill buttons, quantity selector, primary CTA full-width.

5. Cart & Checkout

    - Cart drawer slide-in với backdrop blur; cart items as glass rows.
    - Checkout page minimal: progress steps, summary card with translucent background.

6. Modals / Toasts / Popovers

    - Modal background: heavy blur + dark translucent overlay.
    - Toasts: small rounded glass pill in top-right, auto-dismiss.

7. Filters & Sidebars

    - Collapsible glass panel on desktop; slide-up sheet on mobile.
    - Use toggles and checkboxes with rounded sliders.

8. Microinteractions & Animation
    - Use Framer Motion / CSS transitions: subtle scale, fade, slide.
    - Liquid glass interactions: animated backdrop-filter intensity on hover/focus; subtle `transform: translateZ`/parallax.
    - Loading skeletons: blurred glass placeholders.

Tech / Implementation suggestions (developer-friendly):

-   Frontend: React (or Next.js) + Tailwind CSS (config add CSS variables), Framer Motion or GSAP for animations.
-   CSS: use `backdrop-filter: blur(8px)` + `background: rgba(255,255,255,0.35)` + `border: 1px solid rgba(255,255,255,0.25)` for glass. Add `@supports` fallback (solid with lowered opacity) for browsers not supporting backdrop-filter.
-   Example Tailwind utility classes (custom): `.glass` and `.glass-strong` tạo sẵn trong `tailwind.config.js` dưới `plugins` hoặc `components`.
-   Images: use AVIF/WebP where possible, `loading="lazy"`, responsive `srcset`.
-   Icons: use SVG sprite or icon library (lucide/heroicons).
-   State management: use React Query or SWR for product data; stripe/payment integration for checkout.
-   Testing: Lighthouse, axe-core accessibility checks.

Deliverables (kỳ vọng):

1. Figma/Sketch design file + responsive frames (mobile/tablet/desktop).
2. Component library (React) with Storybook — tất cả components (Header, Card, Product, Modal, Cart Drawer).
3. Tailwind config & base CSS utilities (glass styles, radii, tokens).
4. Sample pages: Home, Collection, Product, Cart, Checkout, Account.
5. Unit/visual tests + brief performance/accessibility report.

Acceptance criteria:

-   UI matches moodboard (rounded, frosted/glass).
-   Mobile-first, mọi component responsive.
-   Glass effect works on supported browsers and degrades gracefully.
-   Page lighthouse score >= 85 (desktop) và >= 70 (mobile).
-   Keyboard accessible + ARIA attributes cho các control.

Phần kỹ thuật — snippet CSS (dùng làm reference):

```css
/* Glass base */
.glass {
    background: rgba(255, 255, 255, 0.28);
    border: 1px solid rgba(255, 255, 255, 0.35);
    backdrop-filter: blur(10px) saturate(120%);
    -webkit-backdrop-filter: blur(10px) saturate(120%);
    border-radius: 16px;
    box-shadow: 0 6px 18px rgba(10, 10, 10, 0.06);
}

/* Stronger glass (cards on hero) */
.glass-strong {
    background: rgba(255, 255, 255, 0.16);
    border: 1px solid rgba(255, 255, 255, 0.18);
    backdrop-filter: blur(18px) saturate(140%);
}

/* Hover interaction */
.glass:hover {
    transform: translateY(-6px) scale(1.01);
    transition: transform 240ms cubic-bezier(0.2, 0.9, 0.2, 1), box-shadow 240ms;
}
```
