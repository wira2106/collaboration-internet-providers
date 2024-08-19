const meta = {
  progress: {
    func: [
      { call: "color", modifier: "temp", argument: "#019A6B" },
      { call: "fail", modifier: "temp", argument: "#6e0000" },
      { call: "location", modifier: "temp", argument: "top" },
      {
        call: "transition",
        modifier: "temp",
        argument: { speed: "1.5s", opacity: "0.6s", termination: 400 }
      }
    ]
  }
};

export { meta };
