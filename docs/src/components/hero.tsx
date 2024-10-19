import { Button } from "./button";

type ComponentProps = {
  className?: string;
  title?: string;
  subtitle?: string;
  children?: React.ReactNode;
};

const Hero = ({ className, title, subtitle, children }: ComponentProps) => {
  return (
    <div className={`w-full ${className}`}>
      {title && <h1 className="h1 mt-10">{title}</h1>}
      {subtitle && <h2 className="h3 mt-12">{subtitle}</h2>}
      <Button
        className="mt-12 block w-fit"
        variant="secondary"
        elementType="link"
        to="/acf-fields-creator/api"
      >
        Get Started
      </Button>
      {children}
    </div>
  );
};

export { Hero };
