<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Garis Panah Animasi</title>
  <style>
    body {
      background: #111;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    svg {
      width: 500px;
      height: 300px;
    }
    path {
      stroke: #00ffcc;
      stroke-width: 3;
      fill: none;
      marker-end: url(#arrowhead);
    }
  </style>
</head>
<body>

<svg viewBox="0 0 500 300">
  <defs>
    <!-- <marker id="arrowhead" markerWidth="10" markerHeight="7" 
            refX="10" refY="3.5" orient="auto">
      <polygon points="0 0, 10 3.5, 0 7" fill="#00ffcc" />
    </marker> -->
  </defs>
  <path id="line" d="M 50 150 Q 250 50, 450 150" />
</svg>

<!-- GSAP CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://assets.codepen.io/16327/DrawSVGPlugin3.min.js"></script>
<script>
  gsap.from("#line", {
    duration: 3,
    drawSVG: "0%",
    ease: "power1.inOut"
  });
</script>

</body>
</html>
