// vite.config.js
import { defineConfig } from "file:///D:/Kerjaan/pmbstikpar/node_modules/vite/dist/node/index.js";
import laravel from "file:///D:/Kerjaan/pmbstikpar/node_modules/laravel-vite-plugin/dist/index.js";
import tailwindcss from "file:///D:/Kerjaan/pmbstikpar/node_modules/tailwindcss/lib/index.js";
import autoprefixer from "file:///D:/Kerjaan/pmbstikpar/node_modules/autoprefixer/lib/autoprefixer.js";
var vite_config_default = defineConfig({
  plugins: [
    laravel({
      input: ["resources/css/app.css", "resources/js/app.js"],
      refresh: true
    })
  ],
  css: {
    postcss: {
      plugins: [
        tailwindcss(),
        autoprefixer()
      ]
    }
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJEOlxcXFxLZXJqYWFuXFxcXHBtYnN0aWtwYXJcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZmlsZW5hbWUgPSBcIkQ6XFxcXEtlcmphYW5cXFxccG1ic3Rpa3BhclxcXFx2aXRlLmNvbmZpZy5qc1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9pbXBvcnRfbWV0YV91cmwgPSBcImZpbGU6Ly8vRDovS2VyamFhbi9wbWJzdGlrcGFyL3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHsgZGVmaW5lQ29uZmlnIH0gZnJvbSAndml0ZSc7XG5pbXBvcnQgbGFyYXZlbCBmcm9tICdsYXJhdmVsLXZpdGUtcGx1Z2luJztcbmltcG9ydCB0YWlsd2luZGNzcyBmcm9tICd0YWlsd2luZGNzcyc7XG5pbXBvcnQgYXV0b3ByZWZpeGVyIGZyb20gJ2F1dG9wcmVmaXhlcic7XG5cbmV4cG9ydCBkZWZhdWx0IGRlZmluZUNvbmZpZyh7XG4gICAgcGx1Z2luczogW1xuICAgICAgICBsYXJhdmVsKHtcbiAgICAgICAgICAgIGlucHV0OiBbJ3Jlc291cmNlcy9jc3MvYXBwLmNzcycsICdyZXNvdXJjZXMvanMvYXBwLmpzJ10sXG4gICAgICAgICAgICByZWZyZXNoOiB0cnVlLFxuICAgICAgICB9KSxcbiAgICBdLFxuICAgIGNzczoge1xuICAgICAgICBwb3N0Y3NzOiB7XG4gICAgICAgICAgICBwbHVnaW5zOiBbXG4gICAgICAgICAgICAgICAgdGFpbHdpbmRjc3MoKSxcbiAgICAgICAgICAgICAgICBhdXRvcHJlZml4ZXIoKSxcbiAgICAgICAgICAgIF0sXG4gICAgICAgIH0sXG4gICAgfSxcbn0pO1xuIl0sCiAgIm1hcHBpbmdzIjogIjtBQUF1UCxTQUFTLG9CQUFvQjtBQUNwUixPQUFPLGFBQWE7QUFDcEIsT0FBTyxpQkFBaUI7QUFDeEIsT0FBTyxrQkFBa0I7QUFFekIsSUFBTyxzQkFBUSxhQUFhO0FBQUEsRUFDeEIsU0FBUztBQUFBLElBQ0wsUUFBUTtBQUFBLE1BQ0osT0FBTyxDQUFDLHlCQUF5QixxQkFBcUI7QUFBQSxNQUN0RCxTQUFTO0FBQUEsSUFDYixDQUFDO0FBQUEsRUFDTDtBQUFBLEVBQ0EsS0FBSztBQUFBLElBQ0QsU0FBUztBQUFBLE1BQ0wsU0FBUztBQUFBLFFBQ0wsWUFBWTtBQUFBLFFBQ1osYUFBYTtBQUFBLE1BQ2pCO0FBQUEsSUFDSjtBQUFBLEVBQ0o7QUFDSixDQUFDOyIsCiAgIm5hbWVzIjogW10KfQo=
