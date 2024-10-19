import { Hero } from "../components";

type ComponentProps = {
  className?: string;
  title?: string;
  subtitle?: string;
  children?: React.ReactNode;
};

const Home = (props: ComponentProps) => {
  return (
    <div {...props}>
      <Hero
        title="Acf Creator"
        subtitle="Create ACF objects, designed specifically for seamless integration with the Flynt theme."
      ></Hero>

      <section>
        <div className="flex w-full flex-col gap-1 empty:hidden first:pt-[3px]">
          <div className="w-full break-words my-8">
            <h3 className="h3">Easy ACF-Fields-Creator</h3>
            <p className="mt-2">
              A user-friendly package for effortlessly generating ACF fields,
              optimized for seamless use with the Flynt theme.
            </p>
            <h4 className="h4 mt-5">Simple and Flexible API</h4>
            <p className="mt-2">
              Quickly introduce custom fields into your WordPress projects with
              minimal setup and easy-to-use methods.
            </p>
            <h4 className="h4 mt-5">Powerful and Versatile</h4>
            <p className="mt-2">
              Beyond basic field creation, support advanced features like
              conditional logic, repeatable fields, and flexible layouts to
              handle any project requirement.
            </p>
            <h4 className="h4 mt-5">Component-oriented Integration</h4>
            <p className="mt-2">
              Manage and group your ACF field definitions within individual
              components, keeping your code modular and organized.
            </p>
          </div>
        </div>
      </section>
    </div>
  );
};

export { Home };
