<style>
  :root{
    --brand-primary: #0066ff;
    --brand-primary-200: #99c2ff;
    --brand-bg: #0b1020;
    --brand-card: rgba(17,24,39,0.6);
  }
  /* Ensure Filament admin root uses darker background */
  .filament-app {
    background: linear-gradient(180deg, #0b0f19 0%, #0b0f19 100%);
  }
  /* Card defaults for our widgets */
  .filament-widget-card, .filament-widget {
    background: var(--brand-card) !important;
    border-color: rgba(255,255,255,0.04) !important;
  }
  /* Headings and text tweaks */
  .filament-widget .text-sm, .filament-widget .text-xs { color: rgba(255,255,255,0.65) }
  .filament-widget .text-white { color: #fff }
</style>
