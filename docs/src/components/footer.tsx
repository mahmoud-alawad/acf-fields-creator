const Footer = () => {
  const date = new Date();
  return (
    <div className="border-t pt-10">
      <div className="container">
        <p>MIT Licensed | Copyright © {date.getFullYear()} Mahmoud ALawad</p>
      </div>
    </div>
  );
};

export { Footer };
