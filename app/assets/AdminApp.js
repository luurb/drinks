import React from "react";
import { HydraAdmin } from "@api-platform/admin";

export default () => (
  <HydraAdmin entrypoint="http://localhost:8006/api" />
);