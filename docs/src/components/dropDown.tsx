import { forwardRef } from "react";

type ComponentProps = React.HTMLAttributes<Element> & {
  active?: boolean;
  title?: string;
};

const DropDown = forwardRef<HTMLElement, ComponentProps>(
  ({ title, active, ...rest }, ref) => {
    return (
      <details
        ref={ref}
        className="group relative flex cursor-pointer flex-col"
        open={active}
      >
        <summary className="-mx-4 rounded-md px-3 py-3 transition-colors duration-100 _no-triangle block select-none outline-none focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-red-brand  dark:focus-visible:ring-gray-100 hover:bg-gray-50 active:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:hover:bg-gray-800 dark:active:bg-gray-700">
          <div className="flex h-5 w-full items-center justify-between font-bold lg:text-sm">
            {title}
            <svg
              xmlns="http://www.w3.org/2000/svg"
              className="h-4 w-4 group-open:rotate-90 rotate-0"
              viewBox="0 0 320 512"
              fill="#53565A"
            >
              <path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z" />
            </svg>
          </div>
        </summary>
        {rest.children}
      </details>
    );
  }
);

export { DropDown };
