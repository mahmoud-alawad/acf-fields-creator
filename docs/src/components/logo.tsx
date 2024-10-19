import { Button } from "./button";

type ComponentProps = {
  className?: string;
  width?: string;
  height?: string;
};
const Logo = ({ className, width, height }: ComponentProps = {}) => {
  return (
    <Button elementType="link" to="/acf-fields-creator/" className={className}>
      <div className="w-full flex items-center">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 127.2 104.88"
          width={width ?? "48"}
          height={height ?? "48"}
          fill="#333333"
        >
          <path
            d="M44.05 32.36L0 104.73h29.82l29.06-47.79z"
            className="fill-primary"
          ></path>
          <path
            d="M97.39 104.88h29.81L63.68 0 48.84 24.42l42.41 70.27z"
            fill="#333333"
          ></path>
        </svg>

        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 105.36 105.89"
          width={width ?? "48"}
          height={height ?? "48"}
          fill="#333333"
        >
          <path
            d="M28.77 41.05a29.07 29.07 0 0 1 5.09-7 26.78 26.78 0 0 1 37.86-.21l.2.21a26 26 0 0 1 5.84 8.69l.6 1.2h27l-.6-2.55a53 53 0 0 0-93-21.71c-.81 1-1.59 2-2.32 3.09l-1 1.35 19.28 19.33z"
            className="fill-primary"
          ></path>
          <path
            d="M73.72 101.88a30.81 30.81 0 0 0 3.59-1.64A53.1 53.1 0 0 0 94.84 85.7a52.46 52.46 0 0 0 9.89-20.82l.6-2.55h-27l-.75.75a26.25 26.25 0 0 1-5.84 8.69 29.46 29.46 0 0 1-7 5.09 12.29 12.29 0 0 1-2.4 1 29.18 29.18 0 0 1-7.94 1.65 33.59 33.59 0 0 1-3.75-.15A26.3 26.3 0 0 1 26.22 55v-.75L4.05 32.06 3 35.21a54.53 54.53 0 0 0-3 17.68 53.19 53.19 0 0 0 53 53 54.32 54.32 0 0 0 20.72-4.01z"
            fill="#333333"
          ></path>
        </svg>

        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 88.85 102.93"
          width={width ?? "48"}
          height={height ?? "48"}
          fill="#333333"
        >
          <path
            d="M25.47 25.47h63.38V0H0v3l25.47 25.47z"
            className="fill-primary"
          ></path>
          <path
            d="M60.68 38.8H24.12L.15 14.68v88.25h25.47V64.28h35.06z"
            fill="#333333"
          ></path>
        </svg>
      </div>
      <div className="w-full">Generator</div>
    </Button>
  );
};

export { Logo };
