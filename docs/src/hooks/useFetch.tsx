import { useState, useEffect } from "react";
type OptionsType = {
  responseType?: "text" | "json" | "arrayBuffer";
};
const useFetch = (url: string, options: OptionsType = {}) => {
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  const [data, setData] = useState<any>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState<Error | null>(null);

  useEffect(() => {
    const fetchData = async () => {
      setIsLoading(true);
      setError(null);

      try {
        const response = await fetch(url);
        if (!response.ok) {
          throw new Error("Failed to fetch the data from the resource");
        }
        if (options.responseType === "text") {
          const result = await response.text();
          setData(result);
          return;
        } else if (options.responseType === "json") {
          const result = await response.json();
          setData(result);
          return;
        } else if (options.responseType === "arrayBuffer") {
          const result = await response.arrayBuffer();
          setData(result);
          return;
        }
      } catch (err: Error) {
        setError(err);
      } finally {
        setIsLoading(false);
      }
    };

    return () => {
      fetchData();
    };
  }, []);

  return { data, isLoading, error };
};

export { useFetch };
