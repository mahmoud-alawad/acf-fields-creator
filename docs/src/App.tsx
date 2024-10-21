import { useEffect } from "react";
import { Header, Footer, DropDown, Button } from "./components";
import { Outlet, useLocation } from "react-router-dom";
import { useMethods } from "./hooks/useMethods";

function App() {
  const { hash } = useLocation();
  const { methods } = useMethods();
  useEffect(() => {
    const hash = window.location.hash.slice(1);
    if (hash) {
      const element = document.getElementById(hash);
      if (element) {
        element.scrollIntoView({ behavior: "smooth" });
      }
    }
  }, [hash]);
  return (
    <div>
      <Header />
      <div className="">
        <div className="container flex">
          <div className="sticky bottom-0 top-16 hidden w-60 flex-col gap-3 border-r self-start overflow-auto py-6 pl-8 pr-6 lg:flex h-[calc(100vh-104px)]">
            <DropDown active={true} title="Getting Started">
              <ul>
                <li>
                  <Button
                    elementType="link"
                    to="/acf-fields-creator/get-started/#installation"
                  >
                    Installation
                  </Button>
                </li>
                <li>
                  <Button
                    className=""
                    elementType="link"
                    to="/acf-fields-creator/get-started/"
                  >
                    guide
                  </Button>
                </li>
              </ul>
            </DropDown>
            <DropDown active={true} title="Methods and Properties">
              <ul>
                {
                  // eslint-disable-next-line @typescript-eslint/no-explicit-any
                  methods.map((method: any, index: number) => (
                    <li key={index} className="py-1">
                      <Button
                        elementType="link"
                        to={`/acf-fields-creator/api#${method.name}`}
                      >
                        {method.name}
                      </Button>
                    </li>
                  ))
                }
              </ul>
            </DropDown>
          </div>
          <div className="px-4 w-full">
            <Outlet />
          </div>
        </div>
      </div>
      <Footer />
    </div>
  );
}

export default App;
