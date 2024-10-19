import { useFetch } from "../hooks/useFetch";
import { useEffect, useState } from "react";

const useMethods = () => {
  const methodsUrl =
    "https://raw.githubusercontent.com/mahmoud-alawad/acf-fields-creator/refs/heads/main/src/Base.php";
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  const [methods, setMethods] = useState<any>([]);

  const { data, error, isLoading } = useFetch(methodsUrl, {
    responseType: "text",
  });

  useEffect(() => {

    const methodPattern = /public function (\w+)\s*\(([^)]*)\)/g;
    const methodsArray = [];
    let match;

    // Using exec() in a loop to find all methods with parameters
    while ((match = methodPattern.exec(data)) !== null) {
      const methodName = match[1]; // Extract method name
      const params = match[2].split(",").map((param) => param.trim()); // Extract parameters

      // Store method details
      methodsArray.push({
        name: methodName,
        params: params.filter((param) => param), // Remove empty params
      });
    }
    console.log(methodsArray);
    
    if (methodsArray.length === 0) {
      setMethods([]);
    } else {
      setMethods(methodsArray);
    }
  }, [data]);

  return {
    methods,
    isLoading,
    error,
  };
};

export { useMethods };
