import { forwardRef, useEffect, useRef, useState } from "react";
import { Hamburger } from "./hamburger";
import { Logo } from "./logo";
import { SideBar } from "./sideBar";
import { useComponentVisible } from "../hooks/useComponentVisible";
import { Button } from "./button";

const links = [
  {
    title: "Guide",
    href: "",
    to: "/acf-fields-creator/get-started",
  },
  {
    title: "Api",
    href: "",
    to: "/acf-fields-creator/api",
  },
  {
    title: "Introduction",
    href: "#introduction",
    to: "",
  },
];

const Links = forwardRef<
  HTMLDivElement,
  {
    links?: { title?: string; href?: string; to?: string }[];
    className?: string;
  }
>(({ links, ...rest }, ref) => {

  return (
    <div ref={ref} {...rest} className={`${rest?.className}`}>
      {links?.map((link, index) => (
        <Button elementType="link" to={link.to} key={index} className="">
          {link.title}
        </Button>
      ))}
    </div>
  );
});

/**
 * The main header component.
 *
 * This component renders the header of the page, containing the logo and
 * the hamburger menu.
 *
 * The header is responsive and will stack the logo and hamburger vertically
 * on small screens.
 *
 * The header is also sticky and will stick to the top of the page when
 * scrolled.
 *
 * @returns {JSX.Element} The header component.
 */
const Header = () => {
  const [active, setActive] = useState(false);
  const hamburgerRef = useRef<Node>(null);
  const { ref, isComponentVisible } = useComponentVisible(true, hamburgerRef);

  useEffect(() => {
    if (!isComponentVisible) {
      setActive(false);
    }
  }, [isComponentVisible]);

  return (
    <div className="border-b">
      <div className="container relative z-10 flex justify-between items-center py-4">
        <Logo className="logo flex items-center flex-wrap" />
        <Hamburger
          ref={hamburgerRef}
          onClick={() => setActive(!active)}
          active={active}
          className="lg:hidden"
        />
        <Links className="hidden lg:flex gap-2" links={links} />
      </div>
      {isComponentVisible && (
        <SideBar
          ref={ref}
          active={active}
          onClose={() => setActive(!active)}
          className="lg:hidden"
        >
          <Links className="flex flex-col gap-2 mt-8" links={links} />
        </SideBar>
      )}
    </div>
  );
};

export { Header };
