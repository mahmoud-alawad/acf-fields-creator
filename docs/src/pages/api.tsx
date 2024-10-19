import { CodeBlock, CopyBlock, dracula } from "react-code-blocks";
import { useFetch } from "../hooks/useFetch";
import { useEffect, useState } from "react";
import { Button } from "../components";
import { useMethods } from "../hooks/useMethods";

type ComponentProps = {
  className?: string;
  title?: string;
  subtitle?: string;
  children?: React.ReactNode;
};

const Api = (props: ComponentProps) => {
  const { isLoading, methods, error } = useMethods();
  const code = `$myFields = (new AcfCreate())`;
  return (
    <div
      {...props}
      className={`flex-1 py-2 md:pl-3 ${props.className} ${
        isLoading ? "animate-pulse" : ""
      }`}
    >
      <h1 className="h2 font-medium">Acf Creator</h1>

      <CodeBlock
        customStyle={{ marginTop: "20px" }}
        theme={dracula}
        text={code}
        language="php"
        showLineNumbers={false}
      />

      {!isLoading &&
        methods.map((method, index) => (
          <div key={index} id={method.name} className="mt-6">
            <div>{method.name}</div>
            <CodeBlock
              customStyle={{ marginTop: "10px" }}
              theme={dracula}
              text={method.params.join("\n ")}
              language="php"
              showLineNumbers={false}
            />
          </div>
        ))}
    </div>
  );
};

export { Api };
