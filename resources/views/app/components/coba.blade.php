<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Animasi Garis GSAP</title>
  <style>
    body {
      background: #111;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    svg {
      width: 600px;
      height: 300px;
    }

    path {
      stroke: #00ffcc;
      stroke-width: 4;
      fill: none;
    }
  </style>
</head>
<body>

<svg viewBox="0 0 600 200" preserveAspectRatio="none">
  <path id="line" d="
    M 0 100 
    Q 50 50, 100 100 
    T 200 100 
    T 300 100 
    T 400 100 
    T 500 100 
    T 600 100
  " />
</svg>

<script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/DrawSVGPlugin.min.js"></script>

<script>
 // use a script tag or an external JS file
 document.addEventListener("DOMContentLoaded", (event) => {
  gsap.registerPlugin(DrawSVGPlugin)
  
  // ✅ Animasi
  gsap.from("#line", {
    duration: 2,
    drawSVG: "0%",
    ease: "power2.inOut"
  });
 });

</script>
</body>
</html>
