import { CodeBlock, dracula } from "react-code-blocks";

type ComponentProps = {
  className?: string;
  title?: string;
  subtitle?: string;
  children?: React.ReactNode;
};

const GetStarted = (props: ComponentProps) => {
  const installationCode = `composer require mahmoud-alawad/acf-fields-generator`;
  const usageCode = `
  use AcfCreator\\Create;
  $myFields = (new AcfCreate())
 `;
  return (
    <div {...props} className="flex-1 py-2 md:pl-3">
      <h3 className="h3 mt-6">Getting Started With Acf Fields Creator</h3>
      <div className="h4 mt-4">Installation</div>
      <section id="installation" className="mt-8">
        <CodeBlock
          customStyle={{ marginTop: "20px" }}
          theme={dracula}
          text={installationCode}
          language="php"
          showLineNumbers={false}
        />
      </section>
      <section id="usage" className="mt-6">
        <h3 className="h3">usage</h3>
        <CodeBlock
          customStyle={{ marginTop: "20px" }}
          theme={dracula}
          text={usageCode}
          language="php"
          showLineNumbers={false}
        />
      </section>
    </div>
  );
};

export { GetStarted };
