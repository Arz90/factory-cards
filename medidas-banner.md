● El carrusel va a ancho completo (100vw), sin contenedor. Las medidas son:

  ┌──────────────────┬───────────────────┬───────┐
  │    Breakpoint    │       Ancho       │ Alto  │
  ├──────────────────┼───────────────────┼───────┤
  │ Desktop (>992px) │ 100% del viewport │ 480px │
  ├──────────────────┼───────────────────┼───────┤
  │ Tablet (≤992px)  │ 100% del viewport │ 320px │
  ├──────────────────┼───────────────────┼───────┤
  │ Mobile (≤576px)  │ 100% del viewport │ 260px │
  └──────────────────┴───────────────────┴───────┘

  Tamaño recomendado para subir banners: 1920 × 480 px

  - La imagen se renderiza con object-fit: cover, así que se recorta si las proporciones no coinciden — el centro siempre se conserva
  - El ratio ideal es 4:1 (o más ancho aún, ej. 16:4)
  - Formatos aceptados: JPG, PNG, WebP
  - Peso máximo: 4 MB (límite del servidor)

  ▎ Si el texto importante de la imagen está a la derecha, ten en cuenta que el overlay izquierdo (rgba(0,0,0,.55)) oscurece esa zona para que el título sea
  legible. Lo ideal es que el sujeto principal esté centrado o a la derecha, y el fondo/degradado a la izquierda.