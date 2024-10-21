import { useEffect, useState } from "react";
import data from "../data/methods.json";
const useMethods = () => {
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  const [methods, setMethods] = useState<any>([]);

  useEffect(() => {
    if (data.length === 0) {
      setMethods([]);
    } else {
      setMethods(data);
    }
  }, []);

  return {
    methods,
  };
};

export { useMethods };
