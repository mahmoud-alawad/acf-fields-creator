import { forwardRef } from "react";

type ComponentProps = React.HTMLAttributes<HTMLDivElement> & {
  active?: boolean;
  onClose: () => void;
  children?: React.ReactNode;
};

const SideBar = forwardRef<HTMLDivElement, ComponentProps>(
  ({ className, active, onClose, children, ...rest }, ref) => {
    return (
      <div
        ref={ref}
        {...rest}
        className={`${className} fixed top-[104px] left-0 pt-1 ${
          active ? "translate-x-0" : "-translate-x-full"
        } flex overflow-hidden pl-[var(--container-x-margin)] pr-4 flex-col min-w-80 bg-white border-r border-t	h-screen  transition-transform duration-300`}
      >
        {children}
        <a
          href="https://github.com/mahmoud-alawad/acf-fields-creator"
          target="_blank"
          rel="noopener noreferrer"
          className="flex items-center"
        >
          GitHub
          <svg
            xmlns="http://www.w3.org/2000/svg"
            aria-hidden="true"
            focusable="false"
            x="0px"
            y="0px"
            viewBox="0 0 100 100"
            width="15"
            height="15"
            className=""
          >
            <path
              fill="currentColor"
              d="M18.8,85.1h56l0,0c2.2,0,4-1.8,4-4v-32h-8v28h-48v-48h28v-8h-32l0,0c-2.2,0-4,1.8-4,4v56C14.8,83.3,16.6,85.1,18.8,85.1z"
            ></path>
            <polygon
              fill="currentColor"
              points="45.7,48.7 51.3,54.3 77.2,28.5 77.2,37.2 85.2,37.2 85.2,14.9 62.8,14.9 62.8,22.9 71.5,22.9"
            ></polygon>
          </svg>
          <span className="sr-only">(opens new window)</span>
        </a>
      </div>
    );
  }
);

export { SideBar };
