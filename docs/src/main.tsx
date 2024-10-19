import "./assets/style/index.css";

import React from "react";
import ReactDOM from "react-dom/client";
import App from "./App.tsx";
import "./assets/style/index.css";

import { RouterProvider, createBrowserRouter } from "react-router-dom";
import { Home, Api, GetStarted } from "./pages";

const router = createBrowserRouter([
  {
    path: "/acf-fields-creator/",
    element: <App />,
    children: [
      {
        path: "/acf-fields-creator/",
        element: <Home />,
      },
    ],
  },
  {
    path: "/acf-fields-creator/",
    element: <App />,
    children: [
      {
        path: "/acf-fields-creator/api",
        element: <Api />,
      },
    ],
  },
  {
    path: "/acf-fields-creator/",
    element: <App />,
    children: [
      {
        path: "/acf-fields-creator/get-started",
        element: <GetStarted />,
      },
    ],
  },
]);

ReactDOM.createRoot(document.getElementById("root") as HTMLElement).render(
  <React.StrictMode>
    <RouterProvider router={router} />
  </React.StrictMode>
);
