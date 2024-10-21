import { CodeBlock, dracula } from "react-code-blocks";
import { useMethods } from "../hooks/useMethods";
import { Button, DropDown } from "../components";

type ComponentProps = {
  className?: string;
  title?: string;
  subtitle?: string;
  children?: React.ReactNode;
};

const Api = (props: ComponentProps) => {
  const { methods } = useMethods();
  const code = `$myFields = (new AcfCreate())`;
  return (
    <div {...props} className={` py-2 md:pl-3 ${props.className} `}>
      <h1 className="h2 font-medium">Acf Creator</h1>
      <DropDown  active={false} title="Methods and Properties" className="lg:hidden">
        <ul className="h-60 overflow-y-auto">
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
      <CodeBlock
        customStyle={{ marginTop: "20px" }}
        theme={dracula}
        text={code}
        language="php"
        showLineNumbers={false}
      />

      {
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        methods.map((method: any, index: number) => (
          <div key={index} id={method.name} className="mt-6">
            <div>{method.name}</div>
            <CodeBlock
              customStyle={{ marginTop: "10px" }}
              theme={dracula}
              text={`   params: ${method.params.join(" ")}
   return: ${method.return}
   description: ${method.description}`}
              language="php"
              showLineNumbers={false}
            />
          </div>
        ))
      }
    </div>
  );
};

export { Api };
