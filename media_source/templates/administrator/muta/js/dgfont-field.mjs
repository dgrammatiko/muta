document.querySelector('select[data-change="defaultFont"]')
  .addEventListener('change', (event) => {
    const def = 'system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue","Noto Sans","Liberation Sans",Arial,sans-serif';
    const emoji = '"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji"';
    document.documentElement.style.setProperty('--bs-font-sans-serif', `${(event.target.value === '' ? def : event.target.value)},${emoji}`);
  });

document.querySelector('select[data-change="monoFont"]')
  .addEventListener('change', (event) => {
    const def = 'SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace';
    const emoji = '"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji"';
    document.documentElement.style.setProperty('--bs-font-monospace', `${(event.target.value === '' ? def : event.target.value)},${emoji}`);
  });
