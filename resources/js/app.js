import "bootstrap";
import "../css/app.css";

import "@fortawesome/fontawesome-free/scss/fontawesome.scss";
import "@fortawesome/fontawesome-free/scss/brands.scss";
import "@fortawesome/fontawesome-free/scss/regular.scss";
import "@fortawesome/fontawesome-free/scss/solid.scss";
import "@fortawesome/fontawesome-free/scss/v4-shims.scss";

import Alpine from "alpinejs";
import persist from "@alpinejs/persist";

Alpine.plugin(persist);
Alpine.start();
