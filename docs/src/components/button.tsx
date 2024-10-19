import { forwardRef, Ref, useEffect } from "react";
import { Link } from "react-router-dom";

type ComponentProps = React.HTMLAttributes<Element> & {
  active?: boolean;
  elementType?: "div" | "button" | "a" | "link";
  variant?: "primary" | "secondary";
};

const Element = forwardRef<HTMLElement, ComponentProps>(
  ({ elementType = "button", variant = "primary", children, ...rest }, ref) => {
    const buttonPrimaryClasses = `text-zinc-900 hover:opacity-75 duration-200 ease-linear no-underline`;
    const buttonSecondaryClasses = `text-white bg-primary rounded-sm px-4 py-2 hover:opacity-75 duration-200 ease-linear no-underline`;
    const buttonClasses =
      variant === "primary" ? buttonPrimaryClasses : buttonSecondaryClasses;
    switch (elementType) {
      case "div":
        return (
          <div
            ref={ref as Ref<HTMLDivElement>}
            {...rest}
            className={`${buttonClasses} ${rest.className}`}
          >
            {children}
          </div>
        );
      case "button":
        return (
          <button
            ref={ref as Ref<HTMLButtonElement>}
            {...rest}
            className={`${buttonClasses} ${rest.className}`}
          >
            {children}
          </button>
        );
      case "a":
        return (
          <a
            ref={ref as Ref<HTMLAnchorElement>}
            {...rest}
            className={`${buttonClasses} ${rest.className}`}
          >
            {children}
          </a>
        );
      case "link":
        return (
          <Link
            ref={ref as Ref<HTMLAnchorElement>}
            to={rest.to}
            {...rest}
            className={`${buttonClasses} ${rest.className}`}
          >
            {children}
          </Link>
        );
      default:
        return <></>;
    }
  }
);

const Button = forwardRef<HTMLElement, ComponentProps>(
  ({ active, elementType = "button", variant = "primary", ...rest }, ref) => {
  
    return (
      <Element
        {...rest}
        elementType={elementType}
        variant={variant}
        ref={ref as Ref<HTMLElement>}
        active={active}
      />
    );
  }
);

export { Button };
