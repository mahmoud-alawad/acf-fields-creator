import { forwardRef } from "react";

type ComponentProps = React.HTMLAttributes<HTMLButtonElement> & {
  active?: boolean;
};

const Hamburger = forwardRef<HTMLButtonElement, ComponentProps>(
  ({ className, active, ...rest }, ref) => {
    return (
      <button ref={ref} {...rest} className={className + " relative group"}>
        <div
          className={`relative flex overflow-hidden items-center justify-center rounded-full w-[50px] h-[50px] transform transition-all 
          duration-200`}
        >
          <div className="flex flex-col justify-between w-[20px] h-[20px] transform transition-all duration-500 origin-center overflow-hidden">
            <div
              className={`bg-dark h-[2px] w-7 transform transition-all duration-500 -translate-x-1 ${
                active ? "-rotate-45" : ""
              }`}
            ></div>
            <div className="bg-dark h-[2px] w-7 rounded transform transition-all duration-500 "></div>
            <div
              className={`bg-dark h-[2px] w-7 transform transition-all duration-500 -translate-x-1 ${
                active ? "rotate-45" : ""
              }`}
            ></div>
          </div>
        </div>
      </button>
    );
  }
);

export { Hamburger };
