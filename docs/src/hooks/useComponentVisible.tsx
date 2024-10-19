import { useState, useEffect, useRef } from "react";

const useComponentVisible = (
  initialIsVisible: boolean,
  trigger?: React.RefObject<Node> | null
) => {
  const [isComponentVisible, setIsComponentVisible] =
    useState(initialIsVisible);

  const ref = useRef<Node>(null);

  const handleClickOutside = (event: Event) => {
    const triggerElement = trigger?.current;
    const componentElement = ref.current;
    
    if (triggerElement && triggerElement.contains(event.target as Node)) {
      setIsComponentVisible(true);
    } else if (
      componentElement &&
      !componentElement.contains(event.target as Node)
    ) {
      setIsComponentVisible(false);
    }
  };

  useEffect(() => {
    document.addEventListener("click", handleClickOutside, true);
    return () => {
      document.removeEventListener("click", handleClickOutside, true);
    };
  }, []);

  return { ref, isComponentVisible, setIsComponentVisible };
};

export { useComponentVisible };
